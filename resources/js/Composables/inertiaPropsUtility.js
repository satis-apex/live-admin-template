import { usePage } from "@inertiajs/inertia-vue3";

export function useInertiaPropsUtility() {
	function iPropsValue(propName, key = '') {
		const valObj = usePage().props.value[propName]
		if (key == '')
			return valObj;
		return key.split('.').reduce(function (prev, curr) {
			return prev ? prev[curr] : null
		}, valObj || self)
	}
	return { iPropsValue };
}