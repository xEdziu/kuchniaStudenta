import React, { useState } from 'react';
import styled from 'styled-components';
import Def1 from '../../assets/avatars/def1.png';
import Def2 from '../../assets/avatars/def2.png';
import Def3 from '../../assets/avatars/def3.png';
import Def4 from '../../assets/avatars/def4.png';
import Def5 from '../../assets/avatars/def5.png';
import Def6 from '../../assets/avatars/def6.png';
import Def7 from '../../assets/avatars/def7.png';
import Def8 from '../../assets/avatars/def8.png';

const DataStyles = styled.div`
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    position: relative;
    .back {
        margin-bottom: 20px;
        position: absolute;
        left: 20px;
        cursor: pointer;
        &:hover {
            color: var(--accent);
        }
    }
    .avatar {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
        transition: 0.3s;
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        &:hover {
            transform: scale(1.03);
        }
    }
    form{
        display: flex;
        flex-direction: column;
        align-items: center;
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            &:last-child {
                margin-bottom: 0;
            }
        }
        button {
            padding: 10px;
            background-color: var(--accent);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    }
    .modal {
        position: fixed; 
        z-index: 1; 
        padding-top: 150px; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%;
        overflow: auto; 
        background-color: rgb(0,0,0); 
        background-color: rgba(0,0,0,0.4); 
        
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 800px;
            .images {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
                flex-wrap: wrap;
                img {
                    width: 170px;
                    height: 170px;
                    object-fit: cover;
                    border-radius: 50%;
                    cursor: pointer;
                    transition: 0.3s;
                    &:hover {
                        transform: scale(1.03);
                    }
                }
            }
        }
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
    }
    }

`;

const DataChange = ({ isData }) => {
    const [showModal, setShowModal] = useState(false);

    const openModal = () => {
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
    };

    const chooseImage = (imageNumber, imageSrc) => {
        document.getElementById('output').src = imageSrc;
        document.getElementById('avatar').value = `def${imageNumber}.png`;
        closeModal();
    };
    return (
        <DataStyles>
            <div className="text-container">
                <span className='back' onClick={() => { isData() }}>&larr; Wróć</span>
                <h2>Zmiana danych</h2>
                <p>W tej sekcji możesz zmienić swoje dane.</p>
                <form>
                    <div className="avatar">
                        <img id="output" src={Def1} alt="avatar" onClick={openModal} />
                    </div>
                    <input type="hidden" id="avatar" name="avatar" value="def1.png" />
                    <input type="text" placeholder="Nick" />
                    <input type="password" placeholder="Hasło" />
                    <input type="password" placeholder="Powtórz hasło" />
                    <button>Zapisz</button>
                </form>
                {showModal && (
                    <div className="modal">
                        <div className="modal-content">
                            <span className="close" onClick={closeModal}>&times;</span>
                            <div className='images'>
                                <img src={Def1} alt="1" onClick={() => chooseImage('1', Def1)} />
                                <img src={Def2} alt="2" onClick={() => chooseImage('2', Def2)} />
                                <img src={Def3} alt="3" onClick={() => chooseImage('3', Def3)} />
                                <img src={Def4} alt="4" onClick={() => chooseImage('4', Def4)} />
                                <img src={Def5} alt="5" onClick={() => chooseImage('5', Def5)} />
                                <img src={Def6} alt="6" onClick={() => chooseImage('6', Def6)} />
                                <img src={Def7} alt="7" onClick={() => chooseImage('7', Def7)} />
                                <img src={Def8} alt="8" onClick={() => chooseImage('8', Def8)} />
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </DataStyles>
    );
};

export default DataChange;
