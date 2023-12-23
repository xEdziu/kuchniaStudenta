import React from 'react';
import styled from 'styled-components';
import HeroImg from '../assets/images/team.svg';

const AboutStyles = styled.div`
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 4rem;
    margin-bottom: 2rem;
    margin-left: 10rem;
    margin-right: 10rem;
    .text-container {
        width: 50%;
        padding-left: 5rem;
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.5rem;
            color: var(--dark);
        }
    }
    .image-container {
        width: 50%;
        img {
            width: 100%;
        }
    }
    @media only screen and (max-width: 768px) {
        flex-direction: column;
        .text-container {
            width: 100%;
            text-align: center;
            padding-left: 0;
        }
        .image-container {
            width: 100%;
            margin-top: 2rem;
        }
    }
`;

const AboutTeam = () => {
    return (
        <AboutStyles>
            <div className="text-container">
                <h1>Kim jesteśmy?</h1>
                <p>Jesteśmy grupą studentów, którzy chcą pomóc innym studentom w gotowaniu.</p>
            </div>
            <div className="image-container">
                <img src={HeroImg} alt="Hero" />
            </div>
        </AboutStyles>
    );
};

export default AboutTeam;