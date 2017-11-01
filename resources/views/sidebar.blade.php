<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!--<div class="user-panel">
        <div class="pull-left image">
          <img src="bower_components/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">&nbsp;</li>
        <!-- Optionally, you can add icons to the links --><!--class="active"-->
        <li id="search"><a href="/"><i class="fa fa-search"></i> <span>New Search</span></a></li>
        <li id="reports"><a href="/reports"><i class="fa fa-file-text-o"></i> <span>Reports</span></a></li>
        @if (Auth::user()->type == 'Admin')
        <li id="account"><a href="account"><i class="fa fa-user"></i> <span>Account</span></a></li>
        <li class="treeview" id="ref">
          <a href="#"><i class="fa fa-link"></i> <span>References</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="padding-top: 5px; padding-bottom: 5px;"><a href="/add-reference"><i class="fa fa-plus"></i> Add Reference</a></li>
            <li style="padding-top: 5px; padding-bottom: 5px;"><a href="/references"><i class="fa fa-eye"></i> View References</a></li>
          </ul>
        </li>
          @endif
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>