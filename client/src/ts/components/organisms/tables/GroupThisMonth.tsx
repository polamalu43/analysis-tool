import Table from 'react-bootstrap/Table';
import { GroupThisMonths } from '../../../type';

type Props = {
  groupThisMonths: GroupThisMonths[];
}

const GropuThisMonth: React.FC<Props> = ({ groupThisMonths }) => {
  console.log(groupThisMonths);
  return (
    <>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>日付</th>
            <th>アクセス数</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Mark</td>
            <td>Otto</td>
          </tr>
          <tr>
            <td>Thornton</td>
            <td>@fat</td>
          </tr>
        </tbody>
      </Table>
    </>
  );
}

export default GropuThisMonth;
