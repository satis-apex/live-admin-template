
const setWithExpiry = function (key, value, ttl = 360000) {
	const now = new Date();
	// `item` is an object which contains the original value
	// as well as the time when it's supposed to expire
	const item = {
		value: value,
		expiry: now.getTime() + ttl
	};
	localStorage.setItem(key, JSON.stringify(item));
}
const getWithExpiry = function (key) {
	const itemStr = localStorage.getItem(key);
	// if the item doesn't exist, return null
	if (!itemStr) {
		return null;
	}
	const item = JSON.parse(itemStr);
	const now = new Date();
	// compare the expiry time of the item with the current time
	if (now.getTime() > item.expiry) {
		// If the item is expired, delete the item from storage
		// and return null
		localStorage.removeItem(key);
		return null;
	}
	return item.value;
}
//error,info,success,warning,default
const flashMessage = function (Message, type = "info", reload = false) {
	type == "" ? (msgType = "info") : (msgType = type);
	this.$toast(Message, {
		timeout: 2000,
		type: msgType,
		showCloseButtonOnHover: true,
		hideProgressBar: true,
		onClose: () => {
			if (reload) location.reload();
		}
	});
}

//error,info,success,warning,default
const flashErrorMessage = function (error, type = "info", reload = false) {
	type == "" ? (msgType = "info") : (msgType = type);
	const message = error.response.data.message;
	this.$toast(message, {
		timeout: 2000,
		type: msgType,
		showCloseButtonOnHover: true,
		hideProgressBar: true,
		onClose: () => {
			if (reload) location.reload();
		}
	});
}
const getKeyByValue = function (object, value) {
	return Object.keys(object).find(key => object[key] === value);
}
const getObjectIndex = function (key, value) {
	for (var i = 0; i < this.length;)
		if (this[i++][key] === value) return --i;
	return -1;
}
const themeOption = function (key) {
	return App.themeOption[key];
}
const siteUrl = function (link = "", opt = "") {
	if (opt == "api") return App.siteUrl + "/api/v1/" + link;
	return App.siteUrl + "/" + link;
}
const getObjectRow = function (jsObj, searchKey, searchValue) {
	let multipleKey;
	let multipleValue;
	let objRow = jsObj.filter(obj => {
		multipleKey = Array.isArray(searchKey);
		multipleValue = Array.isArray(searchValue);
		if (multipleKey && multipleValue) {
			switch (searchKey.length) {
				case 2:
					return (
						obj[searchKey[0]] === searchValue[0] &&
						obj[searchKey[1]] === searchValue[1]
					);
					break;
				case 3:
					return (
						obj[searchKey[0]] === searchValue[0] &&
						obj[searchKey[1]] === searchValue[1] &&
						obj[searchKey[2]] === searchValue[2]
					);
					break;
				case 4:
					return (
						obj[searchKey[0]] === searchValue[0] &&
						obj[searchKey[1]] === searchValue[1] &&
						obj[searchKey[2]] === searchValue[2] &&
						obj[searchKey[3]] === searchValue[3]
					);
					break;
				case 5:
					return (
						bj[searchKey[0]] === searchValue[0] &&
						obj[searchKey[1]] === searchValue[1] &&
						obj[searchKey[2]] === searchValue[2] &&
						obj[searchKey[3]] === searchValue[3] &&
						obj[searchKey[4]] === searchValue[4]
					);
					break;
				default:
					return obj[searchKey] === searchValue;
			}
		} else {
			return obj[searchKey] === searchValue;
		}
	});
	return objRow;
}
const objectToArray = function (obj) {
	return Object.keys(obj).map(key => [Number(key), obj[key]]);
}
const wordToCamel = function (str) {
	return str.replace(/\W+(.)/g, function (match, chr) {
		return chr.toUpperCase();
	});
}
const camelToWord = function (key) {
	return _.startCase(key);
}
const readableWord = function (key) {
	return _.startCase(key);
}
const groupBy = function (objectArray, property) {
	return objectArray.reduce((acc, obj) => {
		const key = obj[property];
		if (!acc[key]) {
			acc[key] = [];
		}
		acc[key].push(obj);
		return acc;
	}, {});
}
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
const filterObject = function (objectArray, keys) {
	let filteredObj = [];
	_.forEach(objectArray, obj => {
		const filteringObj = _.pick(obj, keys);
		filteredObj.push(filteringObj);
	});
	return filteredObj;
}
const verifyRole = function (role) {
	if (window.App.user.role.role_name == role) return true;
	return false;
}
const smoothScrollIntoView = function (identifier) {
	setTimeout(() => {
		document.querySelector(identifier).scrollIntoView({
			behavior: "smooth"
		});
	}, 500);
}
const studentAvatar = function (imageLink) {
	if (imageLink == null) return App.emptyStudentAvatar;
	let http = new XMLHttpRequest();
	http.open("HEAD", imageLink, false);
	http.send();
	if (http.status != 404) return imageLink;
	return App.emptyStudentAvatar;
}
const ifUrlExist = function (url, callback) {
	if (url == '' || url == null)
		return false
	let request = new XMLHttpRequest();
	request.open("GET", url, true);
	request.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded; charset=UTF-8"
	);
	request.setRequestHeader("Accept", "*/*");
	request.onprogress = function (event) {
		let status = event.target.status;
		let statusFirstNumber = status.toString()[0];
		switch (statusFirstNumber) {
			case "2":
				request.abort();
				return callback(true);
			default:
				request.abort();
				return callback(false);
		}
	};
	request.send("");
}
const isAuthorize = function () {
	const userRole = window.App.user.role.role_name;
	const allowedRole = ["su_admin"];
	return allowedRole.includes(userRole.toLowerCase());
}
const mobileAndTabletCheck = function () {
	let check = false;
	(function (a) { if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true; })(navigator.userAgent || navigator.vendor || window.opera);
	return check;
}


export default {
	mobileAndTabletCheck,
	setWithExpiry,
	getWithExpiry,
	flashMessage,
	flashErrorMessage,
	getKeyByValue,
	getObjectIndex,
	themeOption,
	siteUrl,
	getObjectRow,
	objectToArray,
	wordToCamel,
	camelToWord,
	readableWord,
	groupBy,
	exportTable,
	jsonToTableExport,
	filterObject,
	verifyRole,
	smoothScrollIntoView,
	studentAvatar,
	ifUrlExist,
	isAuthorize
}