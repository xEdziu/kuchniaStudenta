import React, { useState, useEffect } from 'react';
import axios from 'axios';

function App() {
    const [message, setMessage] = useState('');
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get(`https://${process.env.REACT_APP_SYMFONY_URL}/api/hello`);
                setMessage(response.data.message);
            } catch (error) {
                console.error('Error fetching data from backend:', error);
            }
        };

        fetchData();
    }, []);

    return (
        <div>
            <p>
                {message}
            </p>
        </div>
    );
}
export default App;
