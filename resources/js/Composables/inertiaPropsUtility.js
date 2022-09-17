import { usePage } from "@inertiajs/inertia-vue3";

export function useInertiaPropsUtility() {
	function propsValue(propName, key) {
		const valObj = usePage().props.value[propName]
		return key.split('.').reduce(function (prev, curr) {
			return prev ? prev[curr] : null
		}, valObj || self)
	}
	return { propsValue };
}