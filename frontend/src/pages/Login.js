import React, { useState } from 'react';
import styled from 'styled-components';
import Guard from '../assets/images/guard.svg';

const LoginStyles = styled.div`
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
        .form-container {
            width:50%;
            background-color: white;
            padding: 3rem;
            border-radius: 5px;
            box-shadow: 0 5px 5px rgba(0,0,0,0.1);
            h1 {
                font-size: 2rem;
                margin-bottom: 2rem;
            }
            form {
                display: flex;
                flex-direction: column;
                input {
                    border: none;
                    font-size: 1rem;
                    border-bottom: 1px solid var(--dark);
                    padding: 0.5rem;
                    margin-bottom: 1.5rem;
                    background-color: transparent;
                    &:focus {
                        outline: none;
                    }
                }
                button {
                    border: 1px solid var(--accent);
                    padding: 0.8rem;
                    font-size: 1.1rem;
                    background-color: var(--accent);
                    color: white;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    &:hover {
                        background-color: transparent;
                        color: var(--accent);
                    }
                }
            }
            .req {
                margin-top: 1rem;
                a {
                    color: var(--accent);
                    text-decoration: none;
                    font-weight: 600;
                    &:hover {
                        text-decoration: underline;
                    }
                }
            }
        }
    }
    .image-container {
        width: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        img {
            width: 100%;
            height: auto;
        }
    }
    @media only screen and (max-width: 768px) {
        flex-direction: column;
        height: auto;
        .container {
            width: 100%;
            .form-container {
                width: 100%;
            }
        }
        .image-container {
            width: 100%;
        }
    }

`;

function Login() {

    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleSubmit = (event) => {
        event.preventDefault();
        console.log('submit');
    }

    return (
        <LoginStyles>
            <div className="container">
                <div className="form-container">
                    <h1>Logowanie</h1>
                    <form onSubmit={handleSubmit} encType='multipart/form-data'>
                        <input
                            type="email"
                            placeholder="Email"
                            value={email}
                            onChange={(event) => setEmail(event.target.value)}
                        />
                        <input
                            type="password"
                            placeholder="Hasło"
                            value={password}
                            onChange={(event) => setPassword(event.target.value)}
                        />
                        <button type="submit">Zaloguj się</button>
                    </form>
                    <p className="req">Nie masz konta? <a href="/register">Zarejestruj się</a></p>
                </div>
            </div>
            <div className="image-container">
                <img src={Guard} alt="Guard" />
            </div>
        </LoginStyles>
    );
}

export default Login;
