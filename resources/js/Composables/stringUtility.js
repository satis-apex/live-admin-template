export function useStringUtility() {
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

	return { wordToCamel, camelToWord, readableWord };
}