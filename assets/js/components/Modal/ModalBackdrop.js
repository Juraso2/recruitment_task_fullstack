import React, {useEffect, useRef} from "react";
import useEventBus from "../../hooks/useEventBus";

const ModalBackdrop = () => {
    const eventBus = useEventBus()
    const backdropRef = useRef(null)

    let timer = null
    const handleModalOpened = () => {
        if (backdropRef.current) {
            backdropRef.current.style.display = 'block'

            timer = setTimeout(() => {
                backdropRef.current.classList.add('show')
                clearTimeout(timer)
            }, 150)
        }
    }

    const handleModalClosed = () => {
        if (backdropRef.current) {
            backdropRef.current.classList.remove('show')

            timer = setTimeout(() => {
                backdropRef.current.style.display = 'none'
                clearTimeout(timer)
            }, 150)
        }
    }

    useEffect(() => {
        eventBus.on('modalOpened', () => {
            handleModalOpened()
        })

        eventBus.on('modalClosed', () => {
            handleModalClosed()
        })

        return () => {
            eventBus.off('modalOpened')
            eventBus.off('modalClosed')
        }
    }, []);

    return (
        <div className="modal-backdrop fade" ref={backdropRef} style={{display: "none"}}/>
    )
}

export default ModalBackdrop;