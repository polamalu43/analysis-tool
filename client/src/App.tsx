import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Home from './ts/pages/Home';
import Import from './ts/pages/Import';
import Graph from './ts/pages/Graph';
import Header from './ts/components/organisms/Header';
import Footer from './ts/components/organisms/Footer';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';

function App() {
  return (
    <div className="App">
      <header>
        <Header />
      </header>
      <main>
        <BrowserRouter>
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/import" element={<Import />} />
            <Route path="/graph" element={<Graph />} />
          </Routes>
        </BrowserRouter>
      </main>
      <footer>
        <Footer />
      </footer>
    </div>
  )
}

export default App
