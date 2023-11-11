import React from "react"
import PropTypes from "prop-types";
import useAlert from "../hooks/useAlert";

const Search = ({label, value, onChange}) => {
    const { showAlert } = useAlert()

    const handleDateChange = (event) => {
        const day = new Date(event.target.value).getUTCDay()

        if (day === 0 || day === 6) {
            showAlert('Weekends are not allowed', 'danger')
            return
        }

        onChange(event.target.value)
    }

    return (
        <div className="col-md-12 row no-gutters mt-5 mb-3 p-3 bg-light">
            <div className="col-md-3 my-0 mb-2 mb-md-0 mr-md-2">
                <div className="form-group mb-0">
                    <label className="sr-only" htmlFor="date">{label}</label>
                    <input type="date" className="form-control" id="date" min="2023-01-01" pattern="\d{4}-\d{2}-\d{2}"
                           required value={value} onChange={handleDateChange}/>
                </div>
            </div>
        </div>
    )
}

Search.propTypes = {
    label: PropTypes.string.isRequired,
    value: PropTypes.string.isRequired,
    onChange: PropTypes.func.isRequired,
}

export default Search