import axios from "axios";
import router from "../../router";


//* create new instance to use with interceptors
const instance = axios.create();
instance.defaults.baseURL = import.meta.env.BASE_URL;
instance.interceptors.request.use(
    async function (config) {
        const token = localStorage.getItem("token") || null;
        config.headers = {
            Authorization: token,
        };

        return config;
    },
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

// Add a response interceptor
instance.interceptors.response.use(
    function (response) {
        // appController = useAppController();

        if (response.data.message && response.config.method != "get") {
            // toast(`${response.data.message}`, {
            //   type: "success",
            // });
        }
        return response;
    },
    function (error) {
        // appController = useAppController();
        // appController.setOverlay(false);

        let errorStatusCodes = [403, 422, 400, 500];
        if (errorStatusCodes.includes(error.response.status)) {
            if (error.response.data.data?.errors) {
                // appController.setToastOpts({
                //   show: true,
                //   title: "Error",
                //   msgs: Object.values(error.response.data.data.errors),
                //   type: "danger",
                // });
                // toast(`${Object.values(error.response.data.data.errors)}`, {
                //   type: "error",
                // });
            } else {
                // appController.setToastOpts({
                //   show: true,
                //   title: "Error",
                //   msg: error.response.data.message,
                //   type: "danger",
                // });
                // toast(`${error.response.data.message}`, {
                //   type: "error",
                // });
            }
        } else if (error.response.status == 401) {
            // appController.setToastOpts({
            //   show: true,
            //   title: "Error",
            //   msg: error.response.data.message,
            //   type: "danger",
            // });
            // toast(`${error.response.data.message}`, {
            //     type: "error",
            // });
            localStorage.removeItem(`token`);
            router.push({
                name: "auth-signin",
            });
        }
        return Promise.reject(error);
    }
);

export default instance;