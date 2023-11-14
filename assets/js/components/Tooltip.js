import React, {useState} from "react";
import PropTypes from "prop-types";

const Tooltip = ({children, text, position}) => {
    return (
        <div className="tooltip-wrapper">
            {children}
            <div className={`tooltip ${position}`} role="tooltip">
                {text}
            </div>
        </div>
    )
}

Tooltip.propTypes = {
    children: PropTypes.node.isRequired,
    text: PropTypes.string.isRequired,
    position: PropTypes.oneOf(['top', 'right', 'bottom', 'left']),
}

Tooltip.defaultProps = {
    position: 'top',
}

export default Tooltip