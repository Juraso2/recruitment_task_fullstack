import React from 'react'

const Skeleton = ({ width, className = null }) => {
    const randomWidth = Math.max(25, Math.random() * 100 + 1) + '%'

    return (
        <div className={`skeleton ${className}`} style={{ width: width || randomWidth }}></div>
    )
}

export default Skeleton
