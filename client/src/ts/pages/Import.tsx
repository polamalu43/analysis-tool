import React, { useState, useCallback, useRef } from 'react';
import axios from 'axios';
import { Container, Form, Button } from 'react-bootstrap';
import { useDropzone } from 'react-dropzone';
import { generateUniqueKey } from '../utility';

const UploadForm: React.FC = () => {
  const [files, setFiles] = useState<File[]>([]);
  const fileRef = useRef<HTMLInputElement>(null);

  const onDrop = useCallback((acceptedFiles: File[]) => {
    setFiles(prevFiles => [...prevFiles, ...acceptedFiles]);
  }, []);

  const { getRootProps, getInputProps, isDragActive } = useDropzone({ onDrop });

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const formData = new FormData();
    files.forEach(file => {
      formData.append('files[]', file);
    });
    try {
      const response = await axios.post(
        'http://localhost/analysis-tool/server/src/import.php',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }
      );
      console.log(response.data);

      setFiles([]);
      if (fileRef.current) {
        fileRef.current.value = '';
      }

    } catch (error) {
      console.error('Error uploading files: ', error);
    }
  };

  // const insertSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
  //   event.preventDefault();
  //   try {
  //     const response = await axios.post(
  //       'http://localhost/analysis-tool/server/src/insert.php',
  //       []
  //     );
  //     console.log(response.data);
  //   } catch (error) {
  //     console.error('Error uploading files: ', error);
  //   }
  // };

  return (
    <Container>
      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="formFileMultiple" className="mb-3">
          <div
            {...getRootProps()}
            style={{
              border: isDragActive ? '2px solid blue' : '1px dashed #ccc',
              padding: '20px',
              textAlign: 'center',
              cursor: 'pointer',
              marginTop: '20px',
            }}
          >
            <input {...getInputProps()} ref={fileRef}/>
              <p>ファイルをドロップ<br/>または<br/>クリックしてファイルを選択して下さい</p>
              <ul style={{listStyle: 'none'}}>
                {files.map(file => (
                  <li key={generateUniqueKey()}>{file.name}</li>
                ))}
              </ul>
          </div>
        </Form.Group>
        <div style={{textAlign: 'center'}}>
          <Button variant="primary" type="submit">
            アップロード
          </Button>
        </div>
      </Form>
      <br />
      {/* <Form onSubmit={insertSubmit}>
        <Button variant="primary" type="submit">
          Insert
        </Button>
      </Form> */}
    </Container>
  );
};

export default UploadForm;
