            <table class="table table-hover media-list">
               <thead>
                  <tr>
                     <th>ĐH</th>
                     <th width="150px;">Thời gian</th>
                     <th style="width: 120px; text-align: center">Nhà mạng</th>
                     <th>Số nạp</th>
                     <th>Loại</th>
                     <th>Số tiền</th>
                     <th>Gộp</th>
                     <th>Đã nạp</th>
                     <th class="text-right" style="width: 85px; text-align: center">Trạng thái</th>
                     <th class="text-right" width="85px; text-align: center;">Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                  <!-- ngRepeat: item in items | itemsPerPage: itemsPerPage -->
                  <tr>
                     <td>T161</td>
                     <td>2018/06/04 14:23:34</td>
                     <td>
                        <div ><img src="images/vtt.png" style="width:70%; min-width: 80px"></div>
                     </td>
                     <td style="font-weight: bold">0984265555</td>
                     <td>
                        <!-- ngIf: item.type == 1 --><!-- ngIf: item.type == 2 -->
                        <div>Trả sau</div>
                        <!-- end ngIf: item.type == 2 --><!-- ngIf: item.type == 3 -->
                     </td>
                     <td style="font-weight: bold">200,000</td>
                     <td style="font-style: italic">
                        <!-- ngIf: item.autoJoin == 'Y' -->
                        <div title="Đơn hàng sẽ được nạp bằng nhiều thẻ mệnh giá khác nhau"><span class="text-success">Yes</span></div>
                        <!-- end ngIf: item.autoJoin == 'Y' --><!-- ngIf: item.autoJoin == 'N' -->
                     </td>
                     <td class="text-success" style="font-weight: bold">150,000</td>
                     <td class="text-right">
                        <!-- ngIf: item.status == 0 --><!-- ngIf: item.status == 99 -->
                        <div>
                           <div class="progress" style="min-width: 80px">
                              <div class="progress-bar progress-bar-striped active progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:75%">75%</div>
                           </div>
                        </div>
                        <!-- end ngIf: item.status == 99 --><!-- ngIf: item.status == 98 --><!-- ngIf: item.status == -2 --><!-- ngIf: item.status == -1 -->
                     </td>
                     <td class="text-right">
                        <!-- ngIf: item.status == 99 -->
                        <div ng-if="item.status == 99" style="min-width: 50px"><a href="javascript:void(0);" title="Tạm dừng" ng-click="pause(item.id)"><i class="fa fa-pause text-info"></i></a> &nbsp;&nbsp; <a href="javascript:void(0);" title="Hủy nạp" ng-click="stop(item.id)"><i class="fa fa-trash text-danger"></i></a> &nbsp;</div>
                        <!-- end ngIf: item.status == 99 --><!-- ngIf: item.status == 98 -->
                     </td>
                  </tr>

               </tbody>
               <tfoot>
                  <tr>
                     <td colspan="5" style="text-align: right">Tổng:</td>
                     <td style="font-weight: bold">300,000</td>
                     <td></td>
                     <td style="font-weight: bold" class="text-success">150,000</td>
                     <td colspan="10" style="text-align: right">Có <strong>2</strong> items.</td>
                  </tr>
                  <tr class="no-border">
                     <td colspan="11" style="text-align: right">
   <dir-pagination-controls on-page-change="getData(newPageNumber)" class="ng-isolate-scope">
      <!-- ngIf: 1 < pages.length -->
      <ul class="pagination ng-scope" ng-if="1 < pages.length">
         <!-- ngIf: boundaryLinks --><!-- ngIf: directionLinks -->
         <li ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }" class="ng-scope disabled"><a href="" ng-click="setCurrent(pagination.current - 1)">‹</a></li>
         <!-- end ngIf: directionLinks --><!-- ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope active"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">1</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">2</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">3</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">4</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">5</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">6</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">7</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope disabled"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">...</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index -->
         <li ng-repeat="pageNumber in pages track by $index" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == '...' }" class="ng-scope"><a href="" ng-click="setCurrent(pageNumber)" class="ng-binding">26</a></li>
         <!-- end ngRepeat: pageNumber in pages track by $index --><!-- ngIf: directionLinks -->
         <li ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }" class="ng-scope"><a href="" ng-click="setCurrent(pagination.current + 1)">›</a></li>
         <!-- end ngIf: directionLinks --><!-- ngIf: boundaryLinks -->
      </ul>
      <!-- end ngIf: 1 < pages.length -->
   </dir-pagination-controls>
</td>
                  </tr>
               </tfoot>
            </table>