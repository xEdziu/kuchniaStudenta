import React from 'react';
import { Link } from 'react-router-dom';
import styled from 'styled-components';

const ButtonStyle = styled.div`
  margin-top: 2rem;
  .button {
    font-size: 1.5rem;
    background-color: var(--accent);
    padding: 0.7em 2em;
    border: 2px solid var(--accent);
    border-radius: 8px;
    display: inline-block;
    color: white;
    transition: 0.3s ease-in-out;
    &:hover {
        background-color: transparent;
        color: var(--accent);
    }
  }
  @media only screen and (max-width: 768px) {
    .button {
      font-size: 1.1rem;
    }
  }
`;

export default function Button({
    btnText = 'test',
    btnLink = 'test',
}) {
    return (
        <ButtonStyle className="button-wrapper">
            <Link className="button" to={btnLink}>
                {btnText}
            </Link>
        </ButtonStyle>
    );
}