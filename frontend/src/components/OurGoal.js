import React from 'react';
import styled from 'styled-components';
import HeroImg from '../assets/images/team2.svg';
import Button from './Button';

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
        padding-left: 8rem;
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
        flex-direction: column-reverse;
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

const AboutSection = () => {
    return (
        <AboutStyles>
            <div className="image-container">
                <img src={HeroImg} alt="Hero" />
            </div>
            <div className="text-container">
                <h1>Czemu taka strona?</h1>
                <p>Bo jesteśmy studentami i wiemy jak to jest, kiedy nie ma się czasu na gotowanie, a jedzenie w stołówce jest niejadalne.</p>
                <Button btnText="Zobacz przepisy" btnLink="/recipes" />
            </div>
        </AboutStyles>
    );
};

export default AboutSection;
