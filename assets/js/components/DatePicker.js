import React, {useState} from "react";
import PropTypes from "prop-types";
import Label from "./Label";
import {findNextWorkingDay, findPreviousWorkingDay, format, formatOptions, isWeekend} from "../helpers/date";
import useAlert from "../hooks/useAlert";

const DatePicker = ({label, className, date, onChange}) => {
    const {showAlert} = useAlert()
    const [prevDate, setPrevDate] = useState(date)
    const id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)

    const handleDateChange = (event) => {
        const date = event.target.value
        if (date === prevDate) {
            return
        }

        setPrevDate(date)

        if (isWeekend(new Date(date))) {
            showAlert('You can\'t choose a weekend', 'danger')
            return
        }

        if (date > format(new Date(), formatOptions)) {
            showAlert('You can\'t choose a date in the future', 'warning')
            return
        }

        onChange(date)
    }

    const handleKeyDown = (event) => {
        if (event.key === 'ArrowUp') {
            const nextDay = findNextWorkingDay(new Date(event.target.value))
            onChange(format(nextDay, formatOptions));
        }

        if (event.key === 'ArrowDown') {
            const previousDay = findPreviousWorkingDay(new Date(event.target.value))
            onChange(format(previousDay, formatOptions));
        }
    }

    return (
        <>
            {label && <Label htmlFor={id} className="col-form-label mr-2">{label}</Label>}
            <div className={className}>
                <input
                    required
                    id={id}
                    type="date"
                    className="form-control"
                    value={date}
                    onChange={handleDateChange}
                    onKeyDown={handleKeyDown}
                />
            </div>
        </>
    )
}

DatePicker.propTypes = {
    label: PropTypes.string,
    className: PropTypes.string,
    date: PropTypes.string.isRequired,
    onChange: PropTypes.func.isRequired
}

export default DatePicker