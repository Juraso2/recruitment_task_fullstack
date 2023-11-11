import React, {useState} from "react"
import AlertContext from "../context/AlertContext"

const ALERT_TIME = 5000

const AlertProvider = ({children}) => {
    const [alerts, setAlerts] = useState([])

    const showAlert = (message, type) => {
        setAlerts([...alerts, {message, type}])

        setTimeout(() => {
            setAlerts(alerts => alerts.slice(1))
        }, ALERT_TIME)
    }

    return (
        <AlertContext.Provider value={{alerts, showAlert}}>
            {children}
        </AlertContext.Provider>
    )
}

export default AlertProvider