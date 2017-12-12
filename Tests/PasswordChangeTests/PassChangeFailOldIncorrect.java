package com.example.tests;

import java.util.regex.Pattern;
import java.util.concurrent.TimeUnit;
import org.testng.annotations.*;
import static org.testng.Assert.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.support.ui.Select;

public class PassChangeFailOldIncorrect {
  private WebDriver driver;
  private String baseUrl;
  private boolean acceptNextAlert = true;
  private StringBuffer verificationErrors = new StringBuffer();

  @BeforeClass(alwaysRun = true)
  public void setUp() throws Exception {
    driver = new FirefoxDriver();
    baseUrl = "http://localhost/NotifyMe/";
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
  }

  @Test
  public void testPassChangeFailOldIncorrect() throws Exception {
    driver.get("http://localhost/NotifyMe/");
    driver.findElement(By.id("emailInput")).click();
    driver.findElement(By.id("emailInput")).clear();
    driver.findElement(By.id("emailInput")).sendKeys("Matthew Manning");
    driver.findElement(By.id("loginInput")).click();
    driver.findElement(By.id("linkToPassChange")).click();
    driver.findElement(By.id("oldPassInput")).click();
    driver.findElement(By.id("oldPassInput")).clear();
    driver.findElement(By.id("oldPassInput")).sendKeys("asdf");
    driver.findElement(By.id("newPassInput")).click();
    driver.findElement(By.id("newPassInput")).clear();
    driver.findElement(By.id("newPassInput")).sendKeys("a");
    driver.findElement(By.id("newPassInput2")).click();
    driver.findElement(By.id("newPassInput2")).clear();
    driver.findElement(By.id("newPassInput2")).sendKeys("a");
    driver.findElement(By.id("changePassInput")).click();
    driver.findElement(By.id("linkToHomepage")).click();
  }

  @AfterClass(alwaysRun = true)
  public void tearDown() throws Exception {
    driver.quit();
    String verificationErrorString = verificationErrors.toString();
    if (!"".equals(verificationErrorString)) {
      fail(verificationErrorString);
    }
  }

  private boolean isElementPresent(By by) {
    try {
      driver.findElement(by);
      return true;
    } catch (NoSuchElementException e) {
      return false;
    }
  }

  private boolean isAlertPresent() {
    try {
      driver.switchTo().alert();
      return true;
    } catch (NoAlertPresentException e) {
      return false;
    }
  }

  private String closeAlertAndGetItsText() {
    try {
      Alert alert = driver.switchTo().alert();
      String alertText = alert.getText();
      if (acceptNextAlert) {
        alert.accept();
      } else {
        alert.dismiss();
      }
      return alertText;
    } finally {
      acceptNextAlert = true;
    }
  }
}
