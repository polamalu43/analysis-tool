import { useState, useEffect } from 'react';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import { Line } from 'react-chartjs-2';
import axios from 'axios';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

interface Logs {
  groupMonths: GroupMonths[];
}
interface GroupMonths {
  month: string;
  count: number;
}

const Graph: React.FC = () => {
  const [logs, setLogs] = useState<Logs>({
    groupMonths: [],
  });

  console.log(logs.groupMonths);

  const data = {
    labels: logs.groupMonths.map((groupMonth) => {
      return groupMonth.month;
    }),
    datasets: [
      {
        label: 'アクセス数',
        data: logs.groupMonths.map((groupMonth) => {
          return groupMonth.count;
        }),
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
      },
    ],
  };

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top' as const,
      },
      title: {
        display: true,
        text: '過去1年のアクセス数 (月)',
      },
    },
    scales: {
      x: {
        reverse: true,
      },
      y: {
        max: 100,
        min: 0,
      },
    }
  };

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
      <Line options={options} data={data} />
    </div>
  );
}

export default Graph;
