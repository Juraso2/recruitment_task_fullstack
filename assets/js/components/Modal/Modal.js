import React, {useEffect, useRef} from "react";
import PropTypes from "prop-types";
import useEventBus from "../../hooks/useEventBus";
import ModalHeader from "./ModalHeader";
import ModalFooter from "./ModalFooter";
import ModalBody from "./ModalBody";

const Modal = ({children, show, size, onClose}) => {
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
                    {children}
                </div>
            </div>
        </div>
    )
}

Modal.propTypes = {
    show: PropTypes.bool.isRequired,
    size: PropTypes.oneOf(['sm', 'lg']),
    onClose: PropTypes.func.isRequired,
}

export default Object.assign(Modal, {
    Body: ModalBody,
    Header: ModalHeader,
    Footer: ModalFooter
})