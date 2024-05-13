import { useState, useEffect } from 'react';
import GroupThisMonth from '../components/organisms/tables/GroupThisMonth';
import { LogsHome as Logs } from '../type';
import axios from 'axios';

const Home: React.FC = () => {
  const [logs, setLogs] = useState<Logs>({
    today: null,
    groupThisMonths: [],
  });

  useEffect(() => {
    fetchLogs();
  }, []);

  const fetchLogs = async() => {
    try {
      const response = await axios.get(
        'http://localhost/analysis-tool/server/src/home.php'
      );
      setLogs(response.data);
      console.log(response.data);
    } catch (error) {
      console.error('Error uploading files: ', error);
    }
  };

  return (
    <div className='home'>
      <h2>今日のアクセス数: {logs.today?.count}</h2>
      <h2>今月のアクセス数（日別）</h2>
      <GroupThisMonth  groupThisMonths={logs.groupThisMonths} />
    </div>
  );
}

export default Home;
