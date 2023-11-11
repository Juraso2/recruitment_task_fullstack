import {useState, useEffect} from "react"
import axios from "axios"

const useAxiosWithAbort = (url) => {
    const controller = new AbortController()

    const [data, setData] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState(null)
    const [shouldRefetch, setShouldRefetch] = useState({})

    const refetch = ()  => setShouldRefetch({})

    const fetchData = async () => {
        try {
            const response = await axios(url, {signal: controller.signal})
            setData(response.data)
            setError(null)
        } catch (error) {
            if (error.response) {
                setError(error.response.data.message)
            } else if (error.request) {
                setError('Something went wrong. Please try again later.')
            } else {
                setError(error.message)
            }
            setData([])
        } finally {
            setLoading(false)
        }
    }

    useEffect(() => {
        fetchData()

        return () => {
            controller.abort()
        }
    }, [shouldRefetch])

    return [data, loading, error, refetch]
}

export default useAxiosWithAbort