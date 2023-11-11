import {createContext} from "react";

const AlertContext = createContext({
    alerts: [],
    showAlert: (message, type) => {}
})

export default AlertContext