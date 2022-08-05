<div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Latest Transations</h4>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th> #Ref No. </th>
                            <th> Name </th>
                            <th> Due Date </th>
                            <th> Amount </th>
                            <th> Status </th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($sqltran as $transaction)
                        {
                            echo '
                          <tr>
                            <td>'.$transaction['id'].'</td>
                            <td class="mynewClass" style="text-transform: capitalize">'.$transaction['purpose'].'</td>
                            <td>'.$transaction['created_on'].'</td>
                            <td>'.$transaction['amount'].'</td>
                            <td>
                              <div class="progress">
                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: '.$transaction['amount'].'%" aria-valuenow="'.$transaction['amount'].'" aria-valuemin="0" aria-valuemax="10000"></div>
                              </div>
                            </td>
                          </tr>';
                        }
                        ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>