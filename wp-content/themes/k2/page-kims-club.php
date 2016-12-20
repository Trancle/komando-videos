<?php 

/*
Template Name: Kim's Club
*/

get_header(); ?>
		
  <section class="content-full join-kims-club clearfix" role="main">
		<?php if (have_posts()): while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>

  <div class="join-table-header clearfix">
  <div class="join-table-intro">
    <div class="kims-club-logo"><span>Join</span><img src="//static.komando.com/websites/common/v2/img/kims-club-logo.png" alt="[LOGO] Kim's Club" /></div>
    <p>Join Kim's Club, your key to total access! Select the plan that's right for you below.</p>
    <p>Every Kim's Club Membership comes with a free 15 day trial.</p>
  </div>
  <div class="join-table-option-tabs">
    <div class="join-table-option join-table-option-basic clearfix" data-plan-url="//club.komando.com/products/basic">
      <span class="join-table-option-name">Basic</span>
      <span class="join-table-option-price-sub">as low as</span>
      <span class="join-table-option-price">$1.10</span><span class="join-table-price-per">/week</span>
    </div>

    <div class="join-table-option join-table-option-premium clearfix" data-plan-url="//club.komando.com/products/premium">
      <div class="join-table-option-best">Customer favorite</div>
      <span class="join-table-option-name">Premium</span>
      <span class="join-table-option-price-sub">as low as</span>
      <span class="join-table-option-price">$1.67</span><span class="join-table-price-per">/week</span>
      <div class="join-table-kim"><img src="//static.komando.com/websites/common/v2/img/join-table-kim.png" alt="Kim Komando" /></div>
    </div>
  </div>
  <span class="join-table-lock"><i class="fa fa-lock"></i></span>
</div>

<div class="join-table">

  <div class="join-table-row">
    <div class="join-table-cell basic premium">
      <div class="header-cell">
        + Instant Access to Shows and Podcasts - on Your Schedule <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Watch or listen to Kim's shows, when and where you want, on just about any device. Or download the podcasts and take them with you.
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell basic premium">
      <div class="header-cell">
        + Automatic Entry in Our Contests <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        We give away everything from tablets to vacations. Members are entered into our contests automatically, every day, without doing a thing!
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell basic premium">
      <div class="header-cell">
        + Free Downloads, How-To Guides, Buying Recommendations and More <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Be in the know the easy way with all of Kim's unbiased advice and guides for members only!
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell basic premium">
      <div class="header-cell">
        + Answers on the Members-Only Message Board <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Post questions, get answers and make friends among Kim's fun and active community.
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell basic premium">
      <div class="header-cell">
        + Crystal-Clear Audio and HD Video <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell clearfix" style="display:block;">
        <iframe class="club-products-sizzle-video" width="300" height="208" src="//www.youtube.com/embed/ie1aqPyumnk?showinfo=0&modestbranding=1&rel=0&autohide=1&vq=hd1080" frameborder="0" allowfullscreen></iframe>No static and no commercials! Members get the highest-quality audio and video along with no long commercial breaks.
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell premium">
      <div class="header-cell">
        + The Live Show - on Your Tablet, Computer, Phone or TV <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        We film every Kim Komando Show with eight HD cameras - it's a blast to watch! Members of Kim's Club can see as well as hear the action - and when the world goes to commercials, members don't! During the breaks, when Kim and her crew chat and horse around, you're in on the action.
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      &nbsp;
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell premium">
      <div class="header-cell">
        + Chat during the Live Show <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Become a Premium Member and you will enjoy the banter of others watching the show while you do. Be sure to join in the conversation! It's fun!
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      &nbsp;
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell premium">
      <div class="header-cell">
        + Priority Email to Speak with Kim <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Your email will be Kim's priority! Kim will review emails and use the questions from Kim's Club members to develop segments and information in newsletters. When a Kim's Club member email question is answered by Kim on the air, we'll recognize the member by calling out their first name and city.
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      &nbsp;
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell premium">
      <div class="header-cell">
        + Exclusive Discounts at Kim's Store <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Get instant special savings in Kim's Store. We extend discounts to Kim's Club members only from time to time!
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      &nbsp;
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell premium">
      <div class="header-cell">
        + Invitation to VIP-Only Events to Meet Kim in Person <i class="fa fa-caret-down"></i>
      </div>

      <div class="description-cell">
        Only Kim's Club members receive special tickets to come see the show when we open our studios or hold events throughout the year!
      </div>
    </div>
    <div class="join-table-cell hide-mobile">
      &nbsp;
    </div>
    <div class="join-table-cell hide-mobile">
      <i class="fa fa-check fa-lg"></i>
    </div>
  </div>

  <div class="join-table-row">
    <div class="join-table-cell">
      <a href="//club.komando.com/products/basic" class="btn btn-gold btn-large join-btn join-btn-basic-mobile basic hide-tablet hide-desktop">Join Now</a>
      <a href="//club.komando.com/products/premium" class="btn btn-gold btn-large join-btn join-btn-premium-mobile premium hide-tablet hide-desktop">Join Now</a>
    </div>
    <div class="join-table-cell hide-mobile">
      <a href="//club.komando.com/products/basic" class="btn btn-gold join-btn basic hide-mobile">Join Now</a>
    </div>
    <div class="join-table-cell hide-mobile">
      <a href="//club.komando.com/products/premium" class="btn btn-gold join-btn premium hide-mobile">Join Now</a>
    </div>
  </div>
</div>

  </section>
	
<?php get_footer(); ?>