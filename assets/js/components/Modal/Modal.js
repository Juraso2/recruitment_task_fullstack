import React, {useEffect, useRef} from "react";
import PropTypes from "prop-types";
import useEventBus from "../../hooks/useEventBus";

const Modal = ({children, show, header, footer, size, onClose}) => {
    const eventBus = useEventBus()
    const modalRef = useRef(null)
    const modalSizeClass = size ? 'modal-' + size : ''

    const handleModalClick = (e) => {
        if (e.target === modalRef.current) {
            onClose()
        }
    }

    let timer = null
    useEffect(() => {
        if (show) {
            eventBus.emit('modalOpened')

            document.body.classList.add('modal-open')
            modalRef.current.style.display = 'block'

            timer = setTimeout(() => {
                modalRef.current.classList.add('show')
                clearTimeout(timer)
            }, 100)
            return
        }

        document.body.classList.remove('modal-open')
        modalRef.current.classList.remove('show')

        timer = setTimeout(() => {
            eventBus.emit('modalClosed')
            modalRef.current.style.display = 'none'
            clearTimeout(timer)
        }, 300)
    }, [show]);

    useEffect(() => {
        return () => {
            eventBus.emit('modalClosed')
            document.body.classList.remove('modal-open')
            clearTimeout(timer)
        }
    }, []);

    return (
        <div className="modal fade" ref={modalRef} tabIndex="-1" role="dialog" onClick={handleModalClick}>
            <div className={`modal-dialog modal-dialog-centered ${modalSizeClass}`} role="document">
                <div className="modal-content">
                    {header &&
                        <div className="modal-header">
                            <h5 className="modal-title">{header}</h5>
                            <button type="button" className="close" aria-label="Close" onClick={onClose}>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    }

                    <div className="modal-body">
                        {children}
                    </div>

                    {footer &&
                        <div className="modal-footer">
                            {footer}
                        </div>
                    }
                </div>
            </div>
        </div>
    )
}

Modal.propTypes = {
    show: PropTypes.bool.isRequired,
    header: PropTypes.string,
    footer: PropTypes.node,
    size: PropTypes.string,
    onClose: PropTypes.func.isRequired,
}

export default Modal