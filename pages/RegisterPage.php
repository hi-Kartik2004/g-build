<section class="section">
    <div class="container h-screen flex flex-col items-center justify-center">
        <div class="card">
            <div class="card__header">
                <h4 class="underline">
                    Register
                </h4>
                <p class="">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit magni obcaecati veniam.
                </p>
            </div>

            <div class="card__content">
                <form action="./php/actions.php?register" method="post" class="flex flex-col gap-2 mt-4">

                    <input type="text" placeholder="Enter your name" name="name" class="border" required />

                    <input type="email" placeholder="Enter Email" class="border" name="email" required />

                    <input type="password" placeholder="Enter Password" name="password" class="border" required />

                    <input type="password" placeholder="Enter Confirm Password" name="confirm_password" class="border" required />

                    <input type="text" placeholder="Enter USN" name="usn" class="border" required />

                    <select name="year" required>
                        <option val="0" disabled selected>Select Student Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                        <option value="5">M.Tech</option>
                    </select>

                    <select name="branch" required>
                        <option val="0" disabled selected>Select Student Branch</option>
                        <option value="CSE">CSE</option>
                        <option value="ISE">ISE</option>
                        <option value="AIML">AIML</option>
                        <option value="ECE">ECE</option>
                        <option value="EEE">EEE</option>
                        <option value="Civil">CIVIL</option>
                        <option value="Mech">MECH</option>
                    </select>

                    <button class="primary__btn mt-2">
                        Register &rarr;
                    </button>

                    <div class="separator"></div>

                    <p class="">
                        By registering you agree to our <a href="https://github.com/hi-kartik2004/g-build" target="_blank">Terms & Conditions</a>
                    </p>

                    <p class="mt-2">
                        Already have an account? <a href="?page=login">Login</a>
                    </p>

                </form>

                <div class="flex w-full justify-around">



                    <!-- <a href="https://github.com/hi-kartik2004/g-build" target="_blank" class="mt-4">Github &rarr;</a>

                    <a href="https://github.com/hi-kartik2004" target="_blank" class="mt-4">Know more &rarr;</a> -->
                </div>
            </div>

            <div class="card__footer">

            </div>
        </div>

    </div>

</section>