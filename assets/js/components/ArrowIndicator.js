import React from "react";
import PropTypes from "prop-types";

const ArrowIndicator = ({from, to}) => {
    const diff = from - to

    if (diff === 0) return (
        <span className="mr-1 text-muted">
            <i className="fa fa-minus" aria-hidden="true"/>
        </span>
    )

    const className = diff > 0 ? 'text-success' : 'text-danger'

    const arrow = diff > 0
        ? '<i class="fa fa-arrow-up" aria-hidden="true"></i>'
        : '<i class="fa fa-arrow-down" aria-hidden="true"></i>'

    return (
        <span className={`mr-1 ${className}`}>
            <span dangerouslySetInnerHTML={{__html: arrow}}/>
        </span>
    )
}

ArrowIndicator.propTypes = {
    from: PropTypes.number.isRequired,
    to: PropTypes.number.isRequired
}

export default ArrowIndicator