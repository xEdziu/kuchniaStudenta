import React from 'react';
import HeroSection from '../components/HeroSection';
import AboutSection from '../components/AboutSection';
import AboutTeam from '../components/AboutTeam';
import OurGoal from '../components/OurGoal';

function Home() {

    return (
        <div>
            <HeroSection />
            <AboutSection />
            <AboutTeam />
            <OurGoal />
        </div>
    );
}
export default Home;
