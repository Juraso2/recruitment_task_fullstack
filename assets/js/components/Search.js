import React from "react"
import PropTypes from "prop-types";
import DatePicker from "./DatePicker";
import {useHistory} from "react-router-dom";

const Search = ({label, value}) => {
    const history = useHistory()

    const handleChange = (date) => {
        if (date) history.push('/exchange-rates/' + date)
    }

    return (
        <div className="col-md-12 row no-gutters mt-5 mb-3 p-3 bg-light">
            <div className="col-lg-6 col-xl-4 form-group d-flex mb-0">
                <DatePicker date={value} onChange={handleChange} className="flex-1" label={label}/>
            </div>
        </div>
    )
}

Search.propTypes = {
    label: PropTypes.string.isRequired,
    value: PropTypes.string.isRequired,
}

export default Search