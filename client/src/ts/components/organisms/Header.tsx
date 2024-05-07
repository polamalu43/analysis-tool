import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import {
  HEADER_TITLE_IMAGE,
  LINKS,
} from '../../const';
import { generateUniqueKey } from '../../utility';

const Header = (): JSX.Element => {
  return (
    <>
      <Navbar
        className="main_bg_color header_nav_bar"
        expand="lg"
      >
        <Container>
          <Navbar.Brand className="header_nav_bar_brand">
            <a href="/">
              <img className='header_title_image' src={HEADER_TITLE_IMAGE} alt=''/>
            </a>
          </Navbar.Brand>
          <Navbar.Toggle aria-controls="basic-navbar-nav" />
          <Navbar.Collapse id="basic-navbar-nav" className="mr-auto">
            <Nav className="ms-auto">
              { LINKS.map((NAV_LINK) => (
                <Nav.Link
                  href={ NAV_LINK.link }
                  key={ generateUniqueKey() }
                  className="link_add_border"
                >
                  {NAV_LINK.text}
                </Nav.Link>
              )) }
            </Nav>
          </Navbar.Collapse>
        </Container>
      </Navbar>
    </>
  );
}

export default Header;
