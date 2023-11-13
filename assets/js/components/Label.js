import React from "react";
import PropTypes from "prop-types";

const Label = ({children, className, htmlFor}) => {
    return (
        <label className={className} htmlFor={htmlFor}>{children}</label>
    )
}

Label.propTypes = {
    children: PropTypes.node.isRequired,
    className: PropTypes.string,
    htmlFor: PropTypes.string.isRequired,
}

export default Label