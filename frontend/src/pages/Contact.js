import React, { useState, useEffect } from 'react';
import styled from 'styled-components';
import Asis from '../assets/images/contact.svg';
import axios from 'axios';
import ReactRecaptcha3 from 'react-google-recaptcha3';
import Swal from 'sweetalert2';

const ContactStyles = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    height: 90vh;
    .container {
        width: 40%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        .form-container {
            width:70%;
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
                input, textarea {
                    border: none;
                    font-size: 1.1rem;
                    font-family: 'Lato', sans-serif;
                    border-bottom: 1px solid var(--dark);
                    padding: 0.5rem;
                    margin-bottom: 1.5rem;
                    background-color: transparent;
                    resize: vertical;
                    max-height: 300px;
                    min-height: 38px;
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
        width: 60%;
        display: flex;
        align-items: center;
        justify-content: center;
        img {
            width: 70%;
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

function Contact() {
    const [email, setEmail] = useState('');
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [token, setToken] = useState('');

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
                setToken(token);
                if (token === undefined) {
                    Swal.fire({
                        title: 'Błąd!',
                        text: 'Potwierdź, że nie jesteś robotem!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }
        );
        const fetchData = async () => {
            Swal.fire({
                title: 'Proszę czekać',
                text: 'Wysyłamy wiadomość',
                icon: "info",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
            });
            try {
                const response = await axios.post(
                    `https://${process.env.REACT_APP_SYMFONY}/api/contact`, {
                    email: email,
                    title: title,
                    message: content,
                    token: token
                });
                Swal.close();
                Swal.fire({
                    title: response.data.title,
                    text: response.data.message,
                    icon: response.data.icon,
                    footer: response.data.footer,
                    confirmButtonText: 'OK'
                });
            } catch (error) {
                console.log(error);
                Swal.close();
                Swal.fire({
                    title: 'Błąd!',
                    text: 'Wystąpił błąd podczas wysyłania wiadomości!',
                    icon: 'error',
                    confirmButtonText: 'Spróbuj ponownie'
                });
            }
        }
        fetchData();
    }

    return (
        <ContactStyles>
            <div className="container">
                <div className="form-container">
                    <h1>Kontakt</h1>
                    <form onSubmit={handleSubmit} encType='multipart/form-data'>
                        <input
                            type="email"
                            placeholder="Twój adres email"
                            value={email}
                            onChange={(event) => setEmail(event.target.value)}
                        />
                        <input
                            type="text"
                            placeholder="Temat wiadomości"
                            value={title}
                            onChange={(event) => setTitle(event.target.value)}
                        />
                        <textarea
                            type="text"
                            placeholder="Treść wiadomości"
                            value={content}
                            onChange={(event) => setContent(event.target.value)}
                        />
                        <button type="submit">Wyślij</button>
                    </form>
                </div>
            </div>
            <div className="image-container">
                <img src={Asis} alt="Asistent" />
            </div>
        </ContactStyles>
    );
}
export default Contact;
