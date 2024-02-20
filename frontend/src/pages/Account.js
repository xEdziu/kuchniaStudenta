import React, { useEffect, useState } from 'react';
import styled from 'styled-components';
import DataChange from '../components/Account/DataChange';

const MyAccountStyles = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;

  h2 {
    margin-bottom: 20px;
  }

  .container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    p {
      margin-bottom: 1rem;
    }
  }

  .logout-button {
    margin-top: 20px;
    padding: 10px;
    background-color: var(--accent);
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
`;

function Account() {
  useEffect(() => {
    if (!localStorage.getItem('sessionKey')) {
      window.location.href = '/login';
    }
  });
  const [data, isData] = useState(false);

  function dataHandler() {
    isData(false);
  }
  return (
    <MyAccountStyles>
      <h2>Moje konto</h2>
      <div className="container">
        {
          data ? <DataChange isData={dataHandler} /> : (
            <>
              <p>Witaj, {atob(localStorage.getItem('sessionKey'))}</p>
              <button
                className="logout-button"
                onClick={() => {
                  window.location.href = '/logout';
                }}> Wyloguj się </button>

              <button
                className="logout-button"
                onClick={() => {
                  isData(true);
                }}> Zmień dane </button>
            </>
          )
        }
      </div>
    </MyAccountStyles>
  );
}
export default Account;
