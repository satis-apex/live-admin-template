export function useObjectUtility() {
	const getKeyByValue = function (object, value) {
		return Object.keys(object).find(key => object[key] === value);
	}
	const getObjectIndex = function (key, value) {
		for (var i = 0; i < this.length;)
			if (this[i++][key] === value) return --i;
		return -1;
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
	const filterObject = function (objectArray, keys, caseSensitive = true) {
		let filteredObj = [];
		_.forEach(objectArray, obj => {
			let filteringObj
			if (caseSensitive) {
				filteringObj = _.pick(obj, keys);
			}
			else {
				filteringObj = Object.fromEntries(Object.entries(obj).filter(([key]) => {
					let hasKey = false;
					keys.forEach(val => {
						if (val.toLowerCase() == key.toLowerCase())
							hasKey = true
					});
					return hasKey
				}));
			}

			filteredObj.push(filteringObj);
		});
		return filteredObj;
	}
	const filterObjectWithGroupedValue = function (objectArray, keys) {
		let filteredObj = [];
		_.forEach(objectArray, obj => {
			const filteringObj = _.pick(obj, keys);
			if (!filteredObj.includes(filteringObj[keys]))
				filteredObj.push(filteringObj[keys]);
		});
		return filteredObj;
	}
	const populateObject = function (objToPopulate, objSource) {
		// let objToPop = Object.assign({}, objToPopulate)
		//let newObj = {};
		_.forEach(objToPopulate, (value, key) => {
			objToPopulate[key] = objSource[key];
		});
		return objToPopulate;
	}

	const sanitizeObject = function (jsObj) {
		return _.omitBy(jsObj, (v) => v === null || v === undefined)
	}

	const resetObjectKey = function (jsObj) {

		const results = jsObj.filter(element => {
			if (Object.keys(element).length !== 0) {
				return true;
			}
			return false;
		});

		return _.filter(results, function (value) {
			return value
		});
	}
	return { getKeyByValue, getObjectIndex, getObjectRow, getObjectIndex, objectToArray, groupBy, filterObject, populateObject, sanitizeObject, resetObjectKey, filterObjectWithGroupedValue };
}