import React, { useState, useEffect } from 'react';
import styled from 'styled-components';
import Guard from '../assets/images/guard.svg';
import axios from 'axios';
import ReactRecaptcha3 from 'react-google-recaptcha3';
import Swal from 'sweetalert2';

const ResetStyles = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    height: 90vh;
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

function Reset() {

    const [email, setEmail] = useState('');

    useEffect(() => {
        ReactRecaptcha3.init(process.env.REACT_APP_SITE_KEY).then(
            (status) => {
                console.log(status);
            }
        );
    }, [])

    const handleSubmit = (event) => {
        event.preventDefault();
        ReactRecaptcha3.getToken().then(
            (token) => {
                if (token === undefined) {
                    Swal.fire({
                        title: 'Błąd!',
                        text: 'Potwierdź, że nie jesteś robotem!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const fetchData = async () => {
                    Swal.fire({
                        title: 'Proszę czekać',
                        text: 'Trwa wysyłanie emaila...',
                        icon: "info",
                        allowOutsideClick: false,
                    });
                    try {
                        const response = await axios.post(`https://${process.env.REACT_APP_SYMFONY}/api/resetPassword`, {
                            email: email,
                            token: token
                        });
                        Swal.close();
                        Swal.fire({
                            title: response.data.title,
                            text: response.data.message,
                            icon: response.data.icon,
                            footer: response.data.footer,
                            confirmButtonText: 'OK'
                        })
                        setEmail('');
                    } catch (error) {
                        Swal.close();
                        console.error('Error fetching data from backend:', error);
                    }
                };

                fetchData();
            }
        );
    }

    return (
        <ResetStyles>
            <div className="container">
                <div className="form-container">
                    <h1>Resetowanie hasła</h1>
                    <form onSubmit={handleSubmit} encType='multipart/form-data'>
                        <input
                            type="email"
                            placeholder="Email"
                            value={email}
                            onChange={(event) => setEmail(event.target.value)}
                        />
                        <button type="submit">Resetuj hasło</button>
                    </form>
                </div>
            </div>
            <div className="image-container">
                <img src={Guard} alt="Guard" />
            </div>
        </ResetStyles>
    );
}

export default Reset;
