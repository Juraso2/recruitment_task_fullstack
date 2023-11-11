import React from "react";
import Alert from "./Alert";
import useAlert from "../hooks/useAlert";

const AlertPopup = () => {
    const {alerts} = useAlert();

    if (!alerts.length) return null;

    return (
        <div className="d-flex flex-column w-25 px-2" style={{right: 0, bottom: 0, position: "fixed"}}>
            {alerts.map((alert, index) => (
                <Alert key={index} message={alert.message} type={alert.type}/>
            ))}
        </div>
    )
}

export default AlertPopup