import React from 'react';
import styled from 'styled-components';

const MyAccountStyles = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;

  .container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;

    p {
      margin-bottom: 1rem;
    }
  }

  .logout-button {
    margin-top: 20px;
    padding: 10px;
    background-color: var(--accent); /* Adjust as needed */
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
`;

function Account() {
    const handleLogout = () => {
        // Handle logout logic here
    };

    return (
        <MyAccountStyles>
            <h2>Moje konto</h2>
            <div className="container">
                <p>Witaj, Kubis10!</p>
            </div>
            <button className="logout-button" onClick={handleLogout}>
                Logout
            </button>
        </MyAccountStyles>
    );
}
export default Account;
