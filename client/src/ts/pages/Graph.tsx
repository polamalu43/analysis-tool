import { useState, useEffect } from 'react';
import axios from 'axios';
import GropupMonthY1 from '../components/organisms/graphs/GropupMonthY1';
import { LogsGraph as Logs } from '../type';


const Graph: React.FC = () => {
  const [logs, setLogs] = useState<Logs>({
    groupMonths: {
      datas: [],
      maxCount: 0
    },
  });

  useEffect(() => {
    fetchLogs();
  }, []);

  const fetchLogs = async () => {
    try {
      const response = await axios.get(
        'http://localhost/analysis-tool/server/src/graph.php'
      );
      setLogs(response.data);
      console.log(response.data);
    } catch (error) {
      console.error('Error uploading files: ', error);
    }
  };

  return (
    <div className='graph'>
      <GropupMonthY1 groupMonths={logs.groupMonths} />
    </div>
  );
}

export default Graph;
