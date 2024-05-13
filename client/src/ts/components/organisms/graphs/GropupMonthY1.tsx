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
import { GroupMonths } from '../../../type';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

type Props = {
  groupMonths: GroupMonths;
}

const GropupMonthY1: React.FC<Props> = ({ groupMonths }) => {
  const { datas, maxCount } = groupMonths;

  const data = {
    labels: datas.map((data) => data.month),
    datasets: [
      {
        label: 'アクセス数',
        data: datas.map((data) => data.count),
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
      y: {
        max: maxCount,
        min: 0,
      },
    }
  };

  return (
    <>
      <Line options={options} data={data} />
    </>
  );
}

export default GropupMonthY1;
