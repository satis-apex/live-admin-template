export function useFlashUtility() {
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

	return {
		flashMessage,
		flashErrorMessage,
	};
}