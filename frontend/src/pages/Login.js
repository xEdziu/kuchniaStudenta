import React, { useState } from 'react';
import styled from 'styled-components';
import Guard from '../assets/images/guard.svg';

const LoginStyles = styled.div`
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 80vh;

  .container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    input {
      width: 300px;
      height: 50px;
      margin: 10px;
      padding: 10px;
      font-size: 1.2rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 300px;
      height: 50px;
      margin: 10px;
      padding: 10px;
      font-size: 1.2rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      background: var(--accent);
      color: #fff;
      cursor: pointer;
      transition: 0.3s ease-in-out;

      &:hover {
        background-color: transparent;
        color: var(--accent);
        border: 1px solid var(--accent);
      }
    }
  }

  @media (max-width: 768px) {
    grid-template-columns: 1fr;
    min-height: unset;

    .image-container {
      display: none;
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
                <h1>ZALOGUJ SIĘ</h1>
                <form onSubmit={handleSubmit}>
                    <input type="email" placeholder="Email" value={email} onChange={(event) => setEmail(event.target.value)} />
                    <input type="password" placeholder="Hasło" value={password} onChange={(event) => setPassword(event.target.value)} />
                    <button type="submit">Zaloguj się</button>
                </form>
            </div>
            <div className="image-container">
                <img src={Guard} alt="Guard" />
            </div>
        </LoginStyles>
    );
}

export default Login;
