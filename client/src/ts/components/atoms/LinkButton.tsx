type Props = {
  href: string;
  caption: string;
}

const LinkButton = ({ href, caption }: Props): JSX.Element => {

  return (
    <>
      <div className='detail_button'>
        <a href={ href }>
          <button>{ caption }</button>
        </a>
      </div>
    </>
  );
}

export default LinkButton;
