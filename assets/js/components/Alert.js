import React, {useState} from "react"
import PropTypes from "prop-types";

const Alert = ({message, type}) => {
    const [show, setShow] = useState(true)
    const alertClass = `alert alert-${type} mt-3`

    const handleClose = (e) => {
        e.preventDefault()
        setShow(false)
    }

    if (!show) return null

    return (
        <div className={alertClass} role="alert">
            {message}
            <button type="button" className="close ml-4" aria-label="Close" onClick={handleClose}>
                <span className="fa fa-times" aria-hidden="true"></span>
            </button>
        </div>
    )
}

Alert.propTypes = {
    message: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
}

export default Alert