import React from 'react';
import styled from 'styled-components';

const RecentStyles = styled.div`
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    width: 100%;
    position: relative;
    .text-container {
        width: 100%;
        max-width: 1200px;
        h2 {
            margin-bottom: 20px;
        }
        .recipe-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            .recipe {
                img {
                    width: 100%;
                    height: 150px;
                    object-fit: cover;
                    border-radius: 8px;
                    margin-bottom: 10px;
                }
                h3 {
                    font-size: 1.2rem;
                    font-weight: 400;
                }
            }
        }
    }

`;

const RecentRecipes = () => {

    return (
        <RecentStyles>
            <div className="text-container">
                <h2>Twoje przepisy</h2>
                <div className="recipe-container">
                    <div className="recipe">
                        <img src="https://via.placeholder.com/150" alt="recipe" />
                        <h3>Nazwa przepisu</h3>
                    </div>
                    <div className="recipe">
                        <img src="https://via.placeholder.com/150" alt="recipe" />
                        <h3>Nazwa przepisu</h3>
                    </div>
                    <div className="recipe">
                        <img src="https://via.placeholder.com/150" alt="recipe" />
                        <h3>Nazwa przepisu</h3>
                    </div>
                    <div className="recipe">
                        <img src="https://via.placeholder.com/150" alt="recipe" />
                        <h3>Nazwa przepisu</h3>
                    </div>
                    <div className="recipe">
                        <img src="https://via.placeholder.com/150" alt="recipe" />
                        <h3>Nazwa przepisu</h3>
                    </div>
                    <div className="recipe">
                        <img src="https://via.placeholder.com/150" alt="recipe" />
                        <h3>Nazwa przepisu</h3>
                    </div>
                </div>
            </div>
        </RecentStyles>
    );
};

export default RecentRecipes;
