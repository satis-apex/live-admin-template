export function useFormUtility() {
	const convertToFormData = function (item) {
		var form_data = new FormData();
		for (var key in item) {
			form_data.append(key, item[key]);
		}
		return form_data;
	}
}