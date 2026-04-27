									<div class="col-md-6">
										<div class="journal-grid jorrnal-blog">
											<div class="jorrnal-thumbnail">
												<img class="img-fluid" src="{{asset('application/public/uploads/journals/'.$row_post->featured_image)}}" alt="{{$row_post->title}}">
											</div>
											<div class="jorrnal-blog-content">
												<div class="j-blog-title">
													<h5><a href="{{ route('front.posts.detail', $row_post->slug)}}"> {{\Str::limit($row_post->title, 30)}}</a></h5>
												</div>
												<div class="j-blog-date-count">
													<span class="blog-date"><i class="fa fa-calendar"></i>{{\Carbon\Carbon::parse($row_post->created_at)->format('M d,Y')}}</span>
													<span class="blog-count"><i class="fa fa-eye"></i>{{$row_post->no_of_views ?? 0}}</span>
												</div>
											</div>
										</div>
									</div>