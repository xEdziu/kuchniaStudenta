import React, { useEffect } from 'react';
import styled from 'styled-components';

const LogoutStyles = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    .container {
        width: 50%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        h1 {
            font-size: 2rem;
            margin-bottom: 2rem;
        }
    }
`;

const Logout = () => {
    useEffect(() => {
        localStorage.removeItem('sessionKey');
        window.location.href = '/login';
    }, []);

    return (
        <LogoutStyles>
            <div className="container">
                <h1>Wylogowywanie...</h1>
            </div>
        </LogoutStyles>
    );
};

export default Logout;
