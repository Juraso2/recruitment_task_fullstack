import axios from "axios";

export const fetchData = async (url, controller) => {
    return axios(url, {signal: controller.signal})
        .then(response => response.data)
}