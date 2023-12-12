import logo from './logo.svg';
import './App.css';
import React, { useState, useEffect } from 'react';
import axios from 'axios';

function App() {
  const [message, setMessage] = useState('');
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(`https://${process.env.REACT_APP_SYMFONY_PORT}/api/hello`);
        setMessage(response.data.message);
      } catch (error) {
        console.error('Error fetching data from backend:', error);
      }
    };

    fetchData();
  }, []);

  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Adrian Goral & Jakub Przybysz
        </p>
        <p className="App-link">
          {message}
        </p>
      </header>
    </div>
  );
}
export default App;
