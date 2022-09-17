export function useExportUtility() {
	const exportTable = function (tblId = "exportTable", fileName = "download") {
		if (tblId == "" || tblId == null) tblId = "exportTable";
		if (fileName == "" || fileName == null) fileName = "download";
		var escapeIndex = [];
		$("#" + tblId + " tr:first-child th.noExl").each(function () {
			escapeIndex.push($(this).index());
		});
		TableExport.prototype.typeConfig.date.assert = value => {
			return false;
		};
		const table = TableExport(document.getElementById(tblId), {
			exportButtons: false,
			formats: ["xlsx"],
			ignoreCols: escapeIndex,
			trimWhitespace: true
		});
		// data instance of table of xlsx format
		const xlsxdata = table.getExportData()[tblId]["xlsx"];
		// custom filename
		const customfilename = fileName;
		// download method
		table.export2file(
			xlsxdata.data,
			xlsxdata.mimeType,
			customfilename,
			xlsxdata.fileExtension
		);
	}
	const jsonToTableExport = function (jsonValues, headerFields, fileName) {
		let col = [];
		const headings = Object.keys(headerFields);

		for (let i = 0; i < jsonValues.length; i++) {
			for (let key in jsonValues[i]) {
				if (col.indexOf(key) === -1) {
					if (headings.includes(key)) col.push(key);
				}
			}
		}
		// CREATE DYNAMIC TABLE.
		let table = document.createElement("table");
		table.setAttribute("id", "exportingTable");
		let thead = table.createTHead();
		let header = thead.insertRow(-1);
		// CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.

		for (let i = 0; i < col.length; i++) {
			if (headings.includes(col[i])) {
				let th = document.createElement("th"); // TABLE HEADER.
				th.innerHTML = headerFields[col[i]];
				header.appendChild(th);
			}
		}

		let tbody = table.appendChild(document.createElement("tbody"));

		// ADD JSON DATA TO THE TABLE AS ROWS.
		for (let i = 0; i < jsonValues.length; i++) {
			let tr = tbody.insertRow(-1);
			for (let j = 0; j < col.length; j++) {
				let tabCell = tr.insertCell(-1);
				tabCell.innerHTML = jsonValues[i][col[j]];
			}
		}
		document.body.appendChild(table);
		this.exportTable("exportingTable", fileName);
		document.body.removeChild(table);
		// console.log(document.getElementById('exportingTable'));
		// return table;
	}
	return { exportTable, jsonToTableExport };
}