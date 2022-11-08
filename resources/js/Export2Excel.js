import { saveAs } from 'file-saver'
import * as XLSX from 'xlsx'

function generateArray(table) {
	var out = [];
	var rows = table.querySelectorAll('tr');
	var ranges = [];
	for (var R = 0; R < rows.length; ++R) {
		var outRow = [];
		var row = rows[R];
		var columns = row.querySelectorAll('td');
		for (var C = 0; C < columns.length; ++C) {
			var cell = columns[C];
			var colspan = cell.getAttribute('colspan');
			var rowspan = cell.getAttribute('rowspan');
			var cellValue = cell.innerText;
			if (cellValue !== "" && cellValue == +cellValue) cellValue = +cellValue;

			//Skip ranges
			ranges.forEach(function (range) {
				if (R >= range.s.r && R <= range.e.r && outRow.length >= range.s.c && outRow.length <= range.e.c) {
					for (var i = 0; i <= range.e.c - range.s.c; ++i) outRow.push(null);
				}
			});

			//Handle Row Span
			if (rowspan || colspan) {
				rowspan = rowspan || 1;
				colspan = colspan || 1;
				ranges.push({
					s: {
						r: R,
						c: outRow.length
					},
					e: {
						r: R + rowspan - 1,
						c: outRow.length + colspan - 1
					}
				});
			};

			//Handle Value
			outRow.push(cellValue !== "" ? cellValue : null);

			//Handle Colspan
			if (colspan)
				for (var k = 0; k < colspan - 1; ++k) outRow.push(null);
		}
		out.push(outRow);
	}
	return [out, ranges];
};

function datenum(v, date1904) {
	if (date1904) v += 1462;
	var epoch = Date.parse(v);
	return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
}

function sheet_from_array_of_arrays(data, opts) {
	var ws = {};
	var range = {
		s: {
			c: 10000000,
			r: 10000000
		},
		e: {
			c: 0,
			r: 0
		}
	};
	for (var R = 0; R != data.length; ++R) {
		for (var C = 0; C != data[R].length; ++C) {
			if (range.s.r > R) range.s.r = R;
			if (range.s.c > C) range.s.c = C;
			if (range.e.r < R) range.e.r = R;
			if (range.e.c < C) range.e.c = C;
			var cell = {
				v: data[R][C]
			};
			if (cell.v == null) continue;
			var cell_ref = XLSX.utils.encode_cell({
				c: C,
				r: R
			});

			if (typeof cell.v === 'number') cell.t = 'n';
			else if (typeof cell.v === 'boolean') cell.t = 'b';
			else if (cell.v instanceof Date) {
				cell.t = 'n';
				cell.z = XLSX.SSF._table[14];
				cell.v = datenum(cell.v);
			} else cell.t = 's';

			ws[cell_ref] = cell;
		}
	}
	if (range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
	return ws;
}

function Workbook() {
	if (!(this instanceof Workbook)) return new Workbook();
	this.SheetNames = [];
	this.Sheets = {};
}

function s2ab(s) {
	var buf = new ArrayBuffer(s.length);
	var view = new Uint8Array(buf);
	for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
	return buf;
}

export function export_table_to_excel(id) {
	var theTable = document.getElementById(id);
	var oo = generateArray(theTable);
	var ranges = oo[1];

	/* original data */
	var data = oo[0];
	var ws_name = "SheetJS";

	var wb = new Workbook(),
		ws = sheet_from_array_of_arrays(data);

	/* add ranges to worksheet */
	// ws['!cols'] = ['apple', 'banan'];
	ws['!merges'] = ranges;

	/* add worksheet to workbook */
	wb.SheetNames.push(ws_name);
	wb.Sheets[ws_name] = ws;

	var wbout = XLSX.write(wb, {
		bookType: 'xlsx',
		bookSST: false,
		type: 'binary'
	});

	saveAs(new Blob([s2ab(wbout)], {
		type: "application/octet-stream"
	}), "test.xlsx")
}

export function export_json_to_excel({
	multiHeader = [],
	header,
	data,
	filename,
	merges = [],
	autoWidth = true,
	bookType = 'xlsx'
} = {}) {
	/* original data */
	filename = filename || 'excel-list'
	data = [...data]
	data.unshift(header);

	for (let i = multiHeader.length - 1; i > -1; i--) {
		data.unshift(multiHeader[i])
	}

	var ws_name = "SheetJS";
	var wb = new Workbook(),
		ws = sheet_from_array_of_arrays(data);

	if (merges.length > 0) {
		if (!ws['!merges']) ws['!merges'] = [];
		merges.forEach(item => {
			ws['!merges'].push(XLSX.utils.decode_range(item))
		})
	}

	if (autoWidth) {
		/*Set the maximum width of each column of the worksheet*/
		const colWidth = data.map(row => row.map(val => {
			/*First determine if it is null/undefined*/
			if (val == null) {
				return {
					'wch': 10
				};
			}
			/*evaluate if first character is an english alphabet */
			else if (val.toString().charCodeAt(0) > 255) {
				return {
					'wch': val.toString().length * 2
				};
			} else {
				return {
					'wch': val.toString().length
				};
			}
		}))
		/*start with the first row*/
		let result = colWidth[0];
		for (let i = 1; i < colWidth.length; i++) {
			for (let j = 0; j < colWidth[i].length; j++) {
				if (result[j]['wch'] < colWidth[i][j]['wch']) {
					result[j]['wch'] = colWidth[i][j]['wch'];
				}
			}
		}
		ws['!cols'] = result;
	}

	/* add worksheet to workbook */
	wb.SheetNames.push(ws_name);
	wb.Sheets[ws_name] = ws;

	var wbout = XLSX.write(wb, {
		bookType: bookType,
		bookSST: false,
		type: 'binary'
	});
	saveAs(new Blob([s2ab(wbout)], {
		type: "application/octet-stream"
	}), `${filename}.${bookType}`);
}

function readerData(rawFile) {

	return new Promise((resolve, reject) => {
		const reader = new FileReader()
		reader.onload = e => {
			const data = e.target.result
			const workbook = XLSX.read(data, { type: 'array' })
			const firstSheetName = workbook.SheetNames[0]
			const worksheet = workbook.Sheets[firstSheetName]
			const header = getHeaderRow(worksheet)
			const results = XLSX.utils.sheet_to_json(worksheet)

			resolve({
				header, results
			})
		}
		reader.readAsArrayBuffer(rawFile)
	})
}

function getHeaderRow(sheet) {
	const headers = []
	const range = XLSX.utils.decode_range(sheet['!ref'])
	let C
	const R = range.s.r
	/* start in the first row */
	for (C = range.s.c; C <= range.e.c; ++C) { /* walk every column in the range */
		const cell = sheet[XLSX.utils.encode_cell({ c: C, r: R })]
		/* find the cell in the first row */
		let hdr = 'UNKNOWN ' + C // <-- replace with your desired default
		if (cell && cell.t) hdr = XLSX.utils.format_cell(cell)
		headers.push(hdr)
	}
	return headers
}

export function export_excel_to_json(rawFile) {
	return readerData(rawFile)
}